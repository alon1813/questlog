import React from "react";
import ReactDOM from "react-dom/client";
import { Toaster } from "react-hot-toast";
import "../css/app.css";
import "./bootstrap"; // Axios + CSRF configurado
import SearchPage from "./components/SearchPage.jsx";

// 🔧 Renderizador global de React
document.addEventListener("DOMContentLoaded", () => {
    const rootElement = document.getElementById("react-search");

    if (rootElement) {
        const root = ReactDOM.createRoot(rootElement);
        root.render(
            <React.StrictMode>
                {/* Toaster global: aparecerá en todas las vistas */}
                <Toaster
                    position="top-right"
                    toastOptions={{
                        style: {
                            background: "#1e293b",
                            color: "#fff",
                            borderRadius: "10px",
                            fontSize: "0.9rem",
                        },
                        success: {
                            iconTheme: { primary: "#4f46e5", secondary: "#fff" },
                        },
                        error: {
                            iconTheme: { primary: "#dc2626", secondary: "#fff" },
                        },
                    }}
                />
                <SearchPage />
            </React.StrictMode>
        );
    }
});
