export default function Card({ children }) {
  return (
    <div
      style={{
        border: "1px solid #eee",
        borderRadius: 14,
        padding: 14,
        background: "#fff",
      }}
    >
      {children}
    </div>
  );
}
