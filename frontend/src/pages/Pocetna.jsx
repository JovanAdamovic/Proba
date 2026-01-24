import { useEffect, useState } from "react";
import { useAuth } from "../auth/AuthContext";
import http from "../api/http";
import Card from "../components/Card";

export default function Pocetna() {
  const { user } = useAuth();
  const isAdmin = user?.uloga === "ADMIN";

  const [counts, setCounts] = useState({
    predmeti: 0,
    zadaci: 0,
    predaje: 0,
  });

  useEffect(() => {
    if (!user?.uloga) return;

    (async () => {
      try {
        const [predmetiRes, zadaciRes, predajeRes] = await Promise.all([
          http.get(isAdmin ? "/predmeti" : "/predmeti/moji"),
          http.get(isAdmin ? "/zadaci" : "/zadaci/moji"),
          isAdmin
            ? http.get("/predaje")
            : user?.uloga === "PROFESOR"
              ? http.get("/predaje/za-moje-predmete")
              : http.get("/predaje/moje"),
        ]);

        const predmeti = predmetiRes.data.data || predmetiRes.data || [];
        const zadaci = zadaciRes.data.data || zadaciRes.data || [];
        const predaje = predajeRes.data.data || predajeRes.data || [];

        setCounts({
          predmeti: predmeti.length,
          zadaci: zadaci.length,
          predaje: predaje.length,
        });
      } catch {
        // ostavi 0 da ne ruši stranicu
      }
    })();
  }, [user?.uloga, isAdmin]);

  return (
    <div style={{ padding: 16, display: "grid", gap: 12 }}>
      <h2>Početna</h2>

      <Card>
        <div>
          <b>Korisnik:</b> {user?.ime} {user?.prezime}
        </div>
        <div>
          <b>Uloga:</b> {user?.uloga}
        </div>
        <div>
          <b>Email:</b> {user?.email}
        </div>
      </Card>

      <div
        style={{
          display: "grid",
          gap: 12,
          gridTemplateColumns: "repeat(3, minmax(0, 1fr))",
        }}
      >
        <Card>
          <div style={{ fontSize: 13, color: "#555" }}>
            {isAdmin ? "Predmeti" : "Moji predmeti"}
          </div>
          <div style={{ fontSize: 28, fontWeight: 800 }}>{counts.predmeti}</div>
        </Card>

        <Card>
          <div style={{ fontSize: 13, color: "#555" }}>
            {isAdmin ? "Zadaci" : "Moji zadaci"}
          </div>
          <div style={{ fontSize: 28, fontWeight: 800 }}>{counts.zadaci}</div>
        </Card>

        <Card>
          <div style={{ fontSize: 13, color: "#555" }}>
            {isAdmin ? "Predaje" : "Moje predaje"}
          </div>
          <div style={{ fontSize: 28, fontWeight: 800 }}>{counts.predaje}</div>
        </Card>
      </div>

      <Card>
        <div style={{ fontSize: 14, color: "#444" }}>
          Ova aplikacija koristi REST API + Sanctum Bearer token. Prikaz podataka
          je filtriran po ulozi (student/profesor/admin).
        </div>
      </Card>
    </div>
  );
}
