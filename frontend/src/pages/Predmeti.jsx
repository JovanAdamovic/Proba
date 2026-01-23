import { useEffect, useState } from "react";
import http from "../api/http";
import Card from "../components/Card";

export default function Predmeti() {
  const [items, setItems] = useState([]);
  const [err, setErr] = useState("");

  useEffect(() => {
    (async () => {
      setErr("");
      try {
        const res = await http.get("/predmeti/moji");
        setItems(res.data.data || res.data);
      } catch (e) {
        setErr(e?.response?.data?.message || "Greška pri učitavanju");
      }
    })();
  }, []);

  return (
    <div style={{ padding: 16, display: "grid", gap: 12 }}>
      <h2>Moji predmeti</h2>
      {err && <div style={{ color: "crimson" }}>{err}</div>}
      {items.map((p) => (
        <Card key={p.id}>
          <div><b>{p.naziv}</b> ({p.sifra})</div>
          <div>Godina: {p.godina_studija}</div>
        </Card>
      ))}
      {!err && items.length === 0 && <div>Nema podataka.</div>}
    </div>
  );
}
