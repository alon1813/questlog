import React, { useState, useEffect } from "react";
import axios from "axios";
import ReactDOM from "react-dom/client";

export default function SearchPage() {
    const [query, setQuery] = useState("");
    const [results, setResults] = useState([]);
    const [type, setType] = useState("anime");
    const [loading, setLoading] = useState(false);

    // --- Lógica de Búsqueda (Sin cambios) ---
    useEffect(() => {
        if (query.length < 2) {
            setResults([]);
            return;
        }

        const delayDebounceFn = setTimeout(() => {
            setLoading(true);
            axios
                .get(`/search`, { params: { query, type } })
                .then((response) => {
                    if (Array.isArray(response.data)) {
                        setResults(response.data);
                    } else {
                        setResults([]);
                    }
                })
                .catch(() => setResults([]))
                .finally(() => setLoading(false));
        }, 400);

        return () => clearTimeout(delayDebounceFn);
    }, [query, type]);

    // --- Lógica de Añadir (Sin cambios) ---
    const addToCollection = async (item) => {
        try {
            const formData = {
                api_id: item.api_id,
                type: item.type,
                title: item.title,
                cover_image_url: item.cover_image_url,
                episodes: item.episodes ?? null,
            };
            const response = await axios.post("/mi-lista/guardar", formData);
            const newId = response.data.user_list_item_id;

            setResults((prev) =>
                prev.map((r) =>
                    r.api_id === item.api_id
                        ? { ...r, in_collection: true, user_list_item_id: newId }
                        : r
                )
            );
        } catch (err) {
            console.error("Error al añadir:", err);
            alert("No se pudo añadir a tu lista.");
        }
    };

    // --- Lógica de Eliminar (Sin cambios) ---
    const removeFromCollection = async (item) => {
        if (!item.user_list_item_id || isNaN(Number(item.user_list_item_id))) {
            console.error("ID inválido para eliminar:", item.user_list_item_id);
            alert("Error: El ID del item es inválido.");
            return;
        }

        try {
            await axios.delete(`/mi-lista/${item.user_list_item_id}`);
            setResults((prev) =>
                prev.map((r) =>
                    r.api_id === item.api_id
                        ? { ...r, in_collection: false, user_list_item_id: null }
                        : r
                )
            );
        } catch (err) {
            console.error("Error al eliminar:", err);
            alert("No se pudo eliminar de tu lista.");
        }
    };

    // ==========================================================
    // --- 🎨 RENDERIZADO (Corregido para Light/Dark y Tamaño) ---
    // ==========================================================
    return (
        // Contenedor principal: fondo claro, fondo oscuro en dark mode
        <div className="bg-slate-100 dark:bg-slate-900 text-gray-900 dark:text-white p-4 sm:p-8 rounded-xl min-h-[400px]">
            
            {/* --- Barra de Búsqueda --- */}
            <div className="relative mb-8">
                {/* Input de Búsqueda: texto oscuro en light, blanco en dark */}
                <input
                    type="text"
                    value={query}
                    onChange={(e) => setQuery(e.target.value)}
                    placeholder={`Buscar ${type === "anime" ? "animes" : "videojuegos"}...`}
                    className="w-full pl-4 pr-36 py-4 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg"
                />

                {/* Selector de Tipo: texto oscuro en light, blanco en dark */}
                <select
                    value={type}
                    onChange={(e) => setType(e.target.value)}
                    className="absolute right-2 top-1/2 -translate-y-1/2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-gray-900 dark:text-white px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 focus:ring-0 focus:outline-none"
                >
                    <option value="anime">Anime</option>
                    <option value="game">Videojuego</option>
                </select>
            </div>

            {/* --- Contenido (Loading, Vacío, o Resultados) --- */}
            <div>
                {loading ? (
                    // --- Estado de Carga ---
                    <div className="flex flex-col items-center justify-center py-16 text-slate-600 dark:text-slate-400">
                        <p className="text-lg font-medium">Buscando...</p>
                    </div>

                ) : results.length > 0 ? (
                    // --- Grid de Resultados ---
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        {results.map((item) => (
                            <div
                                key={item.api_id || item.id}
                                className="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden transition-all duration-300 ease-in-out hover:scale-[1.03] hover:shadow-indigo-500/30 hover:border-slate-300 dark:hover:border-slate-600"
                            >
                                <img
                                    src={item.cover_image_url}
                                    alt={item.title}
                                    className="w-full h-48 object-cover"
                                />
                                <div className="p-4">
                                    <p className="text-sm font-medium uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-1">
                                        {item.type}
                                    </p>
                                    <h3 className="text-lg font-semibold text-gray-900 dark:text-white truncate" title={item.title}>
                                        {item.title}
                                    </h3>
                                    
                                    {/* Botones */}
                                    <div className="mt-4">
                                        {item.in_collection ? (
                                            <button
                                                onClick={() => removeFromCollection(item)}
                                                className="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm w-full font-semibold transition-colors"
                                            >
                                                Eliminar de mi colección
                                            </button>
                                        ) : (
                                            <button
                                                onClick={() => addToCollection(item)}
                                                className="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-sm w-full font-semibold transition-colors"
                                            >
                                                Añadir a mi colección
                                            </button>
                                        )}
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>

                ) : query.length > 1 ? (
                    // --- Estado de No Encontrado ---
                    <div className="flex flex-col items-center justify-center py-16 text-slate-600 dark:text-slate-400">
                        <h3 className="text-xl font-semibold text-gray-900 dark:text-white">No se encontraron resultados</h3>
                        <p className="mt-2">No pudimos encontrar nada para "{query}".</p>
                    </div>
                
                ) : (
                    // --- Estado Inicial (Antes de buscar) ---
                    <div className="flex flex-col items-center justify-center py-16 text-slate-500 dark:text-slate-500">
                        <h3 className="text-xl font-semibold text-gray-900 dark:text-slate-300">¿Qué estás buscando?</h3>
                        <p className="mt-2 text-slate-600 dark:text-slate-400">Empieza a escribir para ver los resultados.</p>
                    </div>
                )}
            </div>
        </div>
    );
}

// --- Montaje de React 18 (Correcto) ---
const el = document.getElementById("react-search");
if (el) {
    const root = ReactDOM.createRoot(el);
    root.render(<SearchPage />);
}