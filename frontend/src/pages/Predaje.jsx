import { useEffect, useState } from "react";
import http from "../api/http";
import Card from "../components/Card";
import Button from "../components/Button";
import { useAuth } from "../auth/AuthContext";

export default function Predaje() {
  const { user } = useAuth();
  const [items, setItems] = useState([]);
  const [err, setErr] = useState("");
  const [busyId, setBusyId] = useState(null);

  async function load() {
    setErr("");
    try {
      const endpoint =
        user?.uloga === "PROFESOR" ? "/predaje/za-moje-predmete" : "/predaje/moje";
      const res = await http.get(endpoint);
      setItems(res.data.data || res.data);
    } catch (e) {
      setErr(e?.response?.data?.message || "Greška pri učitavanju");
    }
  }

  useEffect(() => {
    load();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [user?.uloga]);

  async function pokreniPlagijat(predajaId) {
    setBusyId(predajaId);
    try {
      await http.post(`/predaje/${predajaId}/provera-plagijata`);
      await load();
    } catch (e) {
      alert(e?.response?.data?.message || "Neuspešno");
    } finally {
      setBusyId(null);
    }
  }

  return (
    <div style={{ padding: 16, display: "grid", gap: 12 }}>
      <h2>Predaje</h2>
      {err && <div style={{ color: "crimson" }}>{err}</div>}

      {items.map((p) => (
        <Card key={p.id}>
          <div><b>Predaja #{p.id}</b></div>
          <div>Status: {p.status}</div>
          <div>Ocena: {p.ocena ?? "-"}</div>
          <div>Komentar: {p.komentar ?? "-"}</div>

          {user?.uloga === "PROFESOR" && (
            <div style={{ marginTop: 10 }}>
              <Button onClick={() => pokreniPlagijat(p.id)} disabled={busyId === p.id}>
                {busyId === p.id ? "Proveravam..." : "Pokreni proveru plagijata"}
              </Button>
            </div>
          )}
        </Card>
      ))}

      {!err && items.length === 0 && <div>Nema podataka.</div>}
    </div>
  );
}
