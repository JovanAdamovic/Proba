import { useEffect, useMemo, useState } from "react";
import http from "../api/http";
import Card from "../components/Card";
import Input from "../components/Input";

export default function Zadaci() {
  const [items, setItems] = useState([]);
  const [q, setQ] = useState("");
  const [err, setErr] = useState("");

  useEffect(() => {
    (async () => {
      setErr("");
      try {
        const res = await http.get("/zadaci/moji");
        setItems(res.data.data || res.data);
      } catch (e) {
        setErr(e?.response?.data?.message || "Greška pri učitavanju");
      }
    })();
  }, []);

  const filtered = useMemo(() => {
    const s = q.trim().toLowerCase();
    if (!s) return items;
    return items.filter((z) =>
      (z.naslov || "").toLowerCase().includes(s)
    );
  }, [items, q]);

  return (
    <div style={{ padding: 16, display: "grid", gap: 12 }}>
      <h2>Moji zadaci</h2>
      <Input placeholder="Pretraga po naslovu..." value={q} onChange={(e) => setQ(e.target.value)} />
      {err && <div style={{ color: "crimson" }}>{err}</div>}
      {filtered.map((z) => (
        <Card key={z.id}>
          <div><b>{z.naslov}</b></div>
          <div>Rok: {z.rok_predaje}</div>
          <div style={{ fontSize: 13, color: "#555" }}>{z.opis}</div>
        </Card>
      ))}
      {!err && filtered.length === 0 && <div>Nema podataka.</div>}
    </div>
  );
}
