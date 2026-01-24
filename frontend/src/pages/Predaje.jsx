import { useEffect, useMemo, useState } from "react";
import http from "../api/http";
import Card from "../components/Card";
import Button from "../components/Button";
import Input from "../components/Input";
import Modal from "../components/Modal";
import { useAuth } from "../auth/AuthContext";

export default function Predaje() {
  const { user } = useAuth();
  const isAdmin = user?.uloga === "ADMIN";
  const isProfesor = user?.uloga === "PROFESOR";

  const [items, setItems] = useState([]);
  const [err, setErr] = useState("");
  const [busyId, setBusyId] = useState(null);

  // search
  const [q, setQ] = useState("");

  // modal
  const [open, setOpen] = useState(false);
  const [selected, setSelected] = useState(null);

  // profesor edit
  const [edit, setEdit] = useState({ status: "", ocena: "", komentar: "" });

  async function load() {
    setErr("");
    try {
      const endpoint = isAdmin
        ? "/predaje"
        : isProfesor
          ? "/predaje/za-moje-predmete"
          : "/predaje/moje";

      const res = await http.get(endpoint);
      setItems(res.data.data || res.data || []);
    } catch (e) {
      setErr(e?.response?.data?.message || "Greška pri učitavanju");
    }
  }

  useEffect(() => {
    if (!user?.uloga) return;
    load();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [user?.uloga]);

  const filtered = useMemo(() => {
    const s = q.trim().toLowerCase();
    if (!s) return items;

    return items.filter((p) => {
      const hay = `${p.id} ${p.status ?? ""} ${p.komentar ?? ""} ${p.zadatak?.naslov ?? ""} ${p.student?.email ?? ""}`.toLowerCase();
      return hay.includes(s);
    });
  }, [items, q]);

  function openDetails(p) {
    setSelected(p);
    setEdit({
      status: p.status ?? "",
      ocena: p.ocena ?? "",
      komentar: p.komentar ?? "",
    });
    setOpen(true);
  }

  async function pokreniPlagijat(predajaId) {
    setBusyId(predajaId);
    try {
      await http.post(`/predaje/${predajaId}/provera-plagijata`);
      await load(); // komentar u predaji se ažurira iz backenda
    } catch (e) {
      alert(e?.response?.data?.message || "Neuspešno");
    } finally {
      setBusyId(null);
    }
  }

  async function sacuvajOcenu() {
    if (!selected) return;

    // front validacija (da zadovolji "JS funkcionalnosti")
    const allowed = ["PREDATO", "OCENJENO", "VRACENO", "ZAKASNJENO"];
    if (!allowed.includes(edit.status)) {
      alert("Status mora biti: " + allowed.join(", "));
      return;
    }
    if (edit.ocena !== "") {
      const n = Number(edit.ocena);
      if (Number.isNaN(n) || n < 0 || n > 10) {
        alert("Ocena mora biti broj od 0 do 10.");
        return;
      }
    }

    setBusyId(selected.id);
    try {
      await http.put(`/predaje/${selected.id}`, {
        status: edit.status,
        ocena: edit.ocena === "" ? null : Number(edit.ocena),
        komentar: edit.komentar,
      });
      await load();
      setOpen(false);
    } catch (e) {
      alert(e?.response?.data?.message || "Neuspešno čuvanje");
    } finally {
      setBusyId(null);
    }
  }

  return (
    <div style={{ padding: 16, display: "grid", gap: 12 }}>
      <h2>{isAdmin ? "Predaje" : "Moje predaje"}</h2>

      <div style={{ maxWidth: 420 }}>
        <Input
          placeholder="Pretraga (id/status/komentar/naslov/email)..."
          value={q}
          onChange={(e) => setQ(e.target.value)}
        />
      </div>

      {err && <div style={{ color: "crimson" }}>{err}</div>}

      {filtered.map((p) => (
        <Card key={p.id}>
          <div style={{ display: "flex", justifyContent: "space-between", gap: 12 }}>
            <div style={{ display: "grid", gap: 4 }}>
              <div><b>Predaja #{p.id}</b></div>
              <div>Status: {p.status}</div>
              <div>Ocena: {p.ocena ?? "-"}</div>
              <div>Komentar: {p.komentar ?? "-"}</div>
              {p.zadatak?.naslov && <div>Zadatak: {p.zadatak.naslov}</div>}
            </div>

            <div style={{ display: "grid", gap: 8, alignContent: "start" }}>
              <Button onClick={() => openDetails(p)}>Detalji</Button>

              {isProfesor && (
                <Button onClick={() => pokreniPlagijat(p.id)} disabled={busyId === p.id}>
                  {busyId === p.id ? "Proveravam..." : "Proveri plagijat"}
                </Button>
              )}
            </div>
          </div>
        </Card>
      ))}

      {!err && filtered.length === 0 && <div>Nema podataka.</div>}

      <Modal
        open={open}
        title={selected ? `Predaja #${selected.id}` : "Predaja"}
        onClose={() => setOpen(false)}
      >
        {selected && (
          <div style={{ display: "grid", gap: 10 }}>
            <div><b>Zadatak:</b> {selected.zadatak?.naslov ?? "-"}</div>
            <div><b>Status:</b> {selected.status ?? "-"}</div>
            <div><b>Komentar:</b> {selected.komentar ?? "-"}</div>

            {isProfesor && (
              <div style={{ borderTop: "1px solid #eee", paddingTop: 12, display: "grid", gap: 8 }}>
                <div style={{ fontWeight: 700 }}>Ocenjivanje (profesor)</div>

                <Input
                  placeholder="Status (PREDATO/OCENJENO/VRACENO/ZAKASNJENO)"
                  value={edit.status}
                  onChange={(e) => setEdit((x) => ({ ...x, status: e.target.value }))}
                />
                <Input
                  placeholder="Ocena (0-10)"
                  value={edit.ocena}
                  onChange={(e) => setEdit((x) => ({ ...x, ocena: e.target.value }))}
                />
                <Input
                  placeholder="Komentar"
                  value={edit.komentar}
                  onChange={(e) => setEdit((x) => ({ ...x, komentar: e.target.value }))}
                />

                <div style={{ display: "flex", gap: 8 }}>
                  <Button onClick={sacuvajOcenu} disabled={busyId === selected.id}>
                    {busyId === selected.id ? "Čuvam..." : "Sačuvaj"}
                  </Button>
                  <Button onClick={() => setOpen(false)}>Zatvori</Button>
                </div>
              </div>
            )}
          </div>
        )}
      </Modal>
    </div>
  );
}
