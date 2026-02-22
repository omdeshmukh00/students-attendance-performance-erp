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
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-200 to-blue-100">

      <div className="bg-white w-[420px] rounded-2xl shadow-xl p-10">

        <div className="flex flex-col items-center mb-6">

          <img src="/logo.png" className="w-170px h-170px object-contain" />

        </div>

        <h2 className="text-xl font-semibold text-center mb-6">
          ADMIN LOGIN
        </h2>

        <form onSubmit={handleLogin} className="space-y-4">

          <div>
            <label className="text-sm font-medium text-gray-600">
              Admin mail
            </label>

            <input
              type="email"
              placeholder="Enter Admin mail(eg. [EMAIL_ADDRESS]"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="w-full mt-1 border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <div>
            <label className="text-sm font-medium text-gray-600">
              Password
            </label>

            <input
              type="password"
              placeholder="Password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full mt-1 border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          {error && (
            <p className="text-red-500 text-sm">{error}</p>
          )}

          <button
            disabled={loading}
            className="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition"
          >
            {loading ? "Logging in..." : "LOGIN"}
          </button>

        </form>

      </div>

    </div>
  );
}