import { useAuth } from "../auth/AuthContext";
import Card from "../components/Card";

export default function Dashboard() {
  const { user } = useAuth();

  return (
    <div style={{ padding: 16, display: "grid", gap: 12 }}>
      <h2>Dashboard</h2>

      <Card>
        <div><b>Korisnik:</b> {user?.ime} {user?.prezime}</div>
        <div><b>Uloga:</b> {user?.uloga}</div>
        <div><b>Email:</b> {user?.email}</div>
      </Card>

      <Card>
        <div style={{ fontSize: 14, color: "#444" }}>
          Ova aplikacija koristi REST API + Sanctum Bearer token.
          Prikaz podataka je filtriran po ulozi (student/profesor/admin).
        </div>
      </Card>
    </div>
  );
}
