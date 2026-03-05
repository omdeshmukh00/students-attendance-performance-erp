import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

const getCookie = (name: string) => {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop()?.split(";").shift();
};

export default function LoginPage() {
  useEffect(() => {
    document.title = "Student - Login";
  }, []);

  const [btId, setBtId] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  const navigate = useNavigate();

  const handleLogin = async () => {

    if (!btId.trim()) {
      setError("Enter correct ID");
      return;
    }

    const id = btId.toUpperCase();

    const res = await fetch("http://localhost:8000/login", {
      method: "POST",
      credentials: "include",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        student_id: id
      })
    });

    const data = await res.json();

    if (!res.ok) {
      setError(data.message || "Login failed");
      return;
    }

    navigate(`/dashboard/${id}`);
  };
  // ENTER KEY SUPPORT
  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (e.key === "Enter") handleLogin();
  };


  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center px-4 font-roboto">

      <div className="bg-white shadow-2xl rounded-2xl w-full max-w-md p-10 border border-slate-200">

        {/* LOGO */}
        <div className="flex justify-center mb-6">
          <img
            src="/logo.png"
            alt="College Logo"
            className="w-170px h-170px object-contain"
          />
        </div>

        {/* TITLE */}
        <h1 className="text-center text-2xl font-bold text-slate-800 mt-2 mb-4">
          STUDENT LOGIN
        </h1>

        {/* INPUTS */}
        <div className="space-y-5">

          <div>
            <label className="block text-sm font-semibold text-slate-600 mb-1">
              Student ID
            </label>
            <input
              value={btId}
              onChange={(e) => setBtId(e.target.value)}
              onKeyDown={handleKeyDown}
              placeholder="Student ID (e.g., BT240034CS)"
              className="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500"
            />
          </div>

          <div>
            <label className="block text-sm font-semibold text-slate-600 mb-1">
              Password
            </label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              onKeyDown={handleKeyDown}
              placeholder="Password (e.g., 123456)"
              className="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500"
            />
          </div>

          {/* ERROR MESSAGE */}
          {error && (
            <p className="text-red-500 text-sm font-medium">
              {error}
            </p>
          )}

          {/* LOGIN BUTTON */}
          <button
            onClick={handleLogin}
            disabled={loading}
            className="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 rounded-lg transition-all shadow-lg hover:shadow-orange-500/30 disabled:opacity-60"
          >
            {loading ? "Checking..." : "LOGIN"}
          </button>

        </div>
      </div>
    </div>
  );
}
