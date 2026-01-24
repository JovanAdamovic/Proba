import { useEffect, useMemo, useState } from "react";
import http from "../api/http";
import Card from "../components/Card";
import Input from "../components/Input";
import Button from "../components/Button";
import { useAuth } from "../auth/AuthContext";

export default function Predmeti() {
  const { user } = useAuth();
  const isAdmin = user?.uloga === "ADMIN";

  const [items, setItems] = useState([]);
  const [err, setErr] = useState("");
  const [q, setQ] = useState("");
  const [busyId, setBusyId] = useState(null);

  async function load() {
    setErr("");
    try {
      const endpoint = isAdmin ? "/predmeti" : "/predmeti/moji";
      const res = await http.get(endpoint);
      setItems(res.data.data || res.data || []);
    } catch (e) {
      setErr(e?.response?.data?.message || "Greška pri učitavanju");
    }
  }

  useEffect(() => {
    load();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [isAdmin]);

  const filtered = useMemo(() => {
    const s = q.trim().toLowerCase();
    if (!s) return items;

    return items.filter((p) => {
      const hay = `${p.naziv ?? ""} ${p.sifra ?? ""} ${p.godina_studija ?? ""}`.toLowerCase();
      return hay.includes(s);
    });
  }, [items, q]);

  // ✅ ADMIN brisanje predmeta (bez potvrde)
  async function obrisiPredmet(id) {
    if (!isAdmin) return;
    setBusyId(id);
    try {
      await http.delete(`/predmeti/${id}`);
      await load();
    } catch (e) {
      alert(e?.response?.data?.message || "Greška pri brisanju predmeta");
    } finally {
      setBusyId(null);
    }
  }

  return (
    <div style={{ padding: 16, display: "grid", gap: 12 }}>
      <h2>{isAdmin ? "Predmeti" : "Moji predmeti"}</h2>

      <div style={{ maxWidth: 420 }}>
        <Input
          placeholder="Pretraga (naziv/šifra/godina)..."
          value={q}
          onChange={(e) => setQ(e.target.value)}
        />
      </div>

      {err && <div style={{ color: "crimson" }}>{err}</div>}

      {filtered.map((p) => (
        <Card key={p.id}>
          <div style={{ display: "flex", justifyContent: "space-between", gap: 12 }}>
            <div>
              <div>
                <b>{p.naziv}</b> ({p.sifra})
              </div>
              <div>Godina: {p.godina_studija}</div>
            </div>

            {isAdmin && (
              <div style={{ display: "grid", alignContent: "start" }}>
                <Button onClick={() => obrisiPredmet(p.id)} disabled={busyId === p.id}>
                  {busyId === p.id ? "Brišem..." : "Obriši"}
                </Button>
              </div>
            )}
          </div>
        </Card>
      ))}

      {!err && filtered.length === 0 && <div>Nema podataka.</div>}
    </div>
  );
}
