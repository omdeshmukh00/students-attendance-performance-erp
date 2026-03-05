import { useState, useEffect } from "react";
import { adminLogin } from "../types/admin";

export default function AdminLoginPage() {
  useEffect(() => {
    document.title = "Admin - Login";
  }, []);
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  async function handleLogin(e: React.FormEvent) {
    e.preventDefault();

    setLoading(true);
    setError("");

    try {
      const data = await adminLogin(email, password);

      console.log("Admin logged in:", data);

      localStorage.setItem("admin_token", data.token);

      window.location.href = "/admin/dashboard";
    } catch (err) {
      setError("Invalid email or password");
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">

      <div className="bg-white w-[420px] rounded-2xl shadow-2xl p-10 border border-slate-200">

        <div className="flex flex-col items-center mb-6">

          <img src="/logo.png" className="w-170px h-170px object-contain" />

        </div>

        <h2 className="text-xl font-semibold text-center mb-6">
          ADMIN LOGIN
        </h2>

        <form onSubmit={handleLogin} className="space-y-4">

          <div>
            <label className="text-sm font-medium text-slate-600">
              Admin mail
            </label>

            <input
              type="email"
              placeholder="Enter Admin mail(eg. [EMAIL_ADDRESS]"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="w-full mt-1 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-orange-500"
              required
            />
          </div>

          <div>
            <label className="text-sm font-medium text-slate-600">
              Password
            </label>

            <input
              type="password"
              placeholder="Password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full mt-1 border border-slate-300 rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-orange-500"
              required
            />
          </div>

          {error && (
            <p className="text-red-500 text-sm">{error}</p>
          )}

          <button
            disabled={loading}
            className="w-full bg-orange-600 hover:bg-orange-700 text-white py-2.5 rounded-lg shadow-lg hover:shadow-orange-500/30 font-semibold transition-all mt-4"
          >
            {loading ? "Logging in..." : "LOGIN"}
          </button>

        </form>

      </div>

    </div>
  );
}