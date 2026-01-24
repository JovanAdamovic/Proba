import { useEffect, useMemo, useState } from "react";
import http from "../api/http";
import Card from "../components/Card";
import Input from "../components/Input";
import { useAuth } from "../auth/AuthContext";
import Button from "../components/Button";
import Modal from "../components/Modal";

export default function Zadaci() {
  const { user } = useAuth();
  const isAdmin = user?.uloga === "ADMIN";

  const [items, setItems] = useState([]);
  const [q, setQ] = useState("");
  const [err, setErr] = useState("");
  const [busyId, setBusyId] = useState(null);

  // modal detalji
  const [open, setOpen] = useState(false);
  const [selected, setSelected] = useState(null);

  async function load() {
    setErr("");
    try {
      const endpoint = isAdmin ? "/zadaci" : "/zadaci/moji";
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

    return items.filter((z) => {
      const hay = `${z.naslov ?? ""} ${z.opis ?? ""} ${z.rok_predaje ?? ""}`.toLowerCase();
      return hay.includes(s);
    });
  }, [items, q]);

  function openDetails(z) {
    setSelected(z);
    setOpen(true);
  }

  // ✅ ADMIN brisanje zadatka (bez potvrde)
  async function obrisiZadatak(id) {
    if (!isAdmin) return;
    setBusyId(id);
    try {
      await http.delete(`/zadaci/${id}`);
      await load();
    } catch (e) {
      alert(e?.response?.data?.message || "Greška pri brisanju zadatka");
    } finally {
      setBusyId(null);
    }
  }

  return (
    <div style={{ padding: 16, display: "grid", gap: 12 }}>
      <h2>{isAdmin ? "Zadaci" : "Moji zadaci"}</h2>

      <div style={{ maxWidth: 420 }}>
        <Input
          placeholder="Pretraga (naslov/opis/rok)..."
          value={q}
          onChange={(e) => setQ(e.target.value)}
        />
      </div>

      {err && <div style={{ color: "crimson" }}>{err}</div>}

      {filtered.map((z) => (
        <Card key={z.id}>
          <div style={{ display: "flex", justifyContent: "space-between", gap: 12 }}>
            <div>
              <div><b>{z.naslov}</b></div>
              <div>Rok: {z.rok_predaje}</div>
              <div style={{ fontSize: 13, color: "#555" }}>{z.opis}</div>
            </div>

            <div style={{ display: "grid", gap: 8, alignContent: "start" }}>
              <Button onClick={() => openDetails(z)}>Detalji</Button>

              {isAdmin && (
                <Button onClick={() => obrisiZadatak(z.id)} disabled={busyId === z.id}>
                  {busyId === z.id ? "Brišem..." : "Obriši"}
                </Button>
              )}
            </div>
          </div>
        </Card>
      ))}

      {!err && filtered.length === 0 && <div>Nema podataka.</div>}

      <Modal
        open={open}
        title={selected ? `Zadatak #${selected.id}` : "Zadatak"}
        onClose={() => setOpen(false)}
      >
        {selected && (
          <div style={{ display: "grid", gap: 8 }}>
            <div><b>Naslov:</b> {selected.naslov}</div>
            <div><b>Rok:</b> {selected.rok_predaje}</div>
            <div><b>Opis:</b> {selected.opis ?? "-"}</div>
          </div>
        )}
      </Modal>
    </div>
  );
}
