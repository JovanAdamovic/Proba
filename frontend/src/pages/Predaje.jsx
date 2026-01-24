import { useAuth } from "../auth/AuthContext";
import Card from "../components/Card";
import { useEffect, useState } from "react";
import http from "../api/http";

export default function Pocetna() {
  const { user } = useAuth();

  const isAdmin = user?.uloga === "ADMIN";

  const [brojPredmeta, setBrojPredmeta] = useState(0);
  const [brojZadataka, setBrojZadataka] = useState(0);
  const [brojPredaja, setBrojPredaja] = useState(0);

  useEffect(() => {
    (async () => {
      try {
        // predmeti
        const pRes = await http.get(isAdmin ? "/predmeti" : "/predmeti/moji");
        const pItems = pRes.data.data || pRes.data;
        setBrojPredmeta(pItems.length || 0);

        // zadaci
        const zRes = await http.get(isAdmin ? "/zadaci" : "/zadaci/moji");
        const zItems = zRes.data.data || zRes.data;
        setBrojZadataka(zItems.length || 0);

        // predaje
        const prRes = await http.get(
          isAdmin
            ? "/predaje"
            : user?.uloga === "PROFESOR"
              ? "/predaje/za-moje-predmete"
              : "/predaje/moje"
        );
        const prItems = prRes.data.data || prRes.data;
        setBrojPredaja(prItems.length || 0);
      } catch (e) {
        // možeš i prikaz greške ako želiš
      }
    })();
  }, [isAdmin, user?.uloga]);

  return (
    <div style={{ padding: 16, display: "grid", gap: 12 }}>
      <h2>Početna</h2>

      <Card>
        <div><b>Korisnik:</b> {user?.ime} {user?.prezime}</div>
        <div><b>Uloga:</b> {user?.uloga}</div>
        <div><b>Email:</b> {user?.email}</div>
      </Card>

      <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr 1fr", gap: 12 }}>
        <Card>
          <div style={{ color: "#666", fontSize: 13 }}>
            {isAdmin ? "Predmeti" : "Moji predmeti"}
          </div>
          <div style={{ fontSize: 28, fontWeight: 700 }}>{brojPredmeta}</div>
        </Card>

        <Card>
          <div style={{ color: "#666", fontSize: 13 }}>
            {isAdmin ? "Zadaci" : "Moji zadaci"}
          </div>
          <div style={{ fontSize: 28, fontWeight: 700 }}>{brojZadataka}</div>
        </Card>

        <Card>
          <div style={{ color: "#666", fontSize: 13 }}>
            {isAdmin ? "Predaje" : "Moje predaje"}
          </div>
          <div style={{ fontSize: 28, fontWeight: 700 }}>{brojPredaja}</div>
        </Card>
      </div>

      <Card>
        <div style={{ fontSize: 14, color: "#444" }}>
          Ova aplikacija koristi REST API + Sanctum Bearer token. Prikaz podataka je filtriran po ulozi
          (student/profesor/admin).
        </div>
      </Card>
    </div>
  );
}
