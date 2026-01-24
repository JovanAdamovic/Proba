import { useMemo, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../auth/AuthContext";
import Button from "../components/Button";
import Input from "../components/Input";
import Card from "../components/Card";

export default function Login() {
  const { login } = useAuth();
  const nav = useNavigate();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [err, setErr] = useState("");
  const [busy, setBusy] = useState(false);

  const canSubmit = useMemo(() => {
    const e = email.trim();
    const p = password.trim();
    if (!e || !p) return false;
    if (!e.includes("@")) return false;
    return true;
  }, [email, password]);

  async function onSubmit(e) {
    e.preventDefault();
    setErr("");

    if (!canSubmit) {
      setErr("Unesi ispravan email i lozinku.");
      return;
    }

    setBusy(true);
    try {
      await login(email.trim(), password);
      nav("/");
    } catch (e2) {
      // login u AuthContext baca Error(message), pa uzmi message
      setErr(e2?.message || "Login nije uspeo");
    } finally {
      setBusy(false);
    }
  }

  return (
    <div style={{ maxWidth: 420, margin: "40px auto" }}>
      <Card>
        <h2>Login</h2>

        <form onSubmit={onSubmit} style={{ display: "grid", gap: 10 }}>
          <div>
            <label>Email</label>
            <Input
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="npr. student@test.com"
            />
          </div>

          <div>
            <label>Lozinka</label>
            <Input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="••••••••"
            />
          </div>

          {err && <div style={{ color: "crimson" }}>{err}</div>}

          <Button type="submit" disabled={!canSubmit || busy}>
            {busy ? "Ulogujem..." : "Uloguj se"}
          </Button>
        </form>
      </Card>
    </div>
  );
}
