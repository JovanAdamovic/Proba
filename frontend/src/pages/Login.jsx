import { useState } from "react";
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

  async function onSubmit(e) {
    e.preventDefault();
    setErr("");
    try {
      await login(email, password);
      nav("/dashboard");
    } catch (e2) {
      setErr(e2?.response?.data?.message || "Login nije uspeo");
    }
  }

  return (
    <div style={{ maxWidth: 420, margin: "40px auto" }}>
      <Card>
        <h2>Login</h2>
        <form onSubmit={onSubmit} style={{ display: "grid", gap: 10 }}>
          <div>
            <label>Email</label>
            <Input value={email} onChange={(e) => setEmail(e.target.value)} />
          </div>
          <div>
            <label>Lozinka</label>
            <Input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
          </div>
          {err && <div style={{ color: "crimson" }}>{err}</div>}
          <Button type="submit">Uloguj se</Button>
        </form>
      </Card>
    </div>
  );
}
