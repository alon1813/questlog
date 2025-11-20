import React, { useState, useEffect } from "react";
import axios from "axios";
import ReactDOM from "react-dom/client";
import toast, { Toaster } from "react-hot-toast";
import SearchIcon from "./icons/SearchIcon";
import AddIcon from "./icons/AddIcon";
import RemoveIcon from "./icons/RemoveIcon";


export default function SearchPage() {
    const [query, setQuery] = useState("");
    const [results, setResults] = useState([]);
    const [type, setType] = useState("anime");
    const [loading, setLoading] = useState(false);

    const handleSearch = () => {
        if (query.trim().length < 2) {
            setResults([]);
            return;
        }
        setLoading(true);
        axios
            .get(`/search`, { params: { query, type } })
            .then((response) => {
                setResults(Array.isArray(response.data) ? response.data : []);
            })
            .catch(() => setResults([]))
            .finally(() => setLoading(false));
    };


    useEffect(() => {
        if (query.length < 2) {
            setResults([]);
            return;
        }
        const delayDebounceFn = setTimeout(() => {
            handleSearch();
        }, 400);
        return () => clearTimeout(delayDebounceFn);
    }, [query, type]);


    const handleKeyDown = (e) => {
        if (e.key === "Enter") handleSearch();
    };

    const addToCollection = async (item) => {
        try {
            const response = await axios.post("/mi-lista/guardar", {
                api_id: item.api_id,
                type: item.type,
                title: item.title,
                cover_image_url: item.cover_image_url,
                episodes: item.episodes ?? null,
            });
            const newId = response.data.user_list_item_id;

            if (!newId) {
                toast.error("Error: El servidor no devolvió un ID.");
                console.error("Respuesta de 'guardar' sin ID:", response.data);
                return;
            }

            setResults((prev) =>
                prev.map((r) =>
                    r.api_id === item.api_id
                        ? { ...r, in_collection: true, user_list_item_id: newId }
                        : r
                )
            );
            toast.success(`✅ ${item.title} añadido a tu colección`);
        } catch (err) {
            toast.error("No se pudo añadir a tu lista.");
        }
    };

    const removeFromCollection = async (item) => {
        if (!item.user_list_item_id) {
            toast.error("Error: El ID del ítem no está definido.");
            console.error("Intento de eliminar sin user_list_item_id:", item);
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
            toast("🗑️ Eliminado de tu colección");
        } catch (err) {
            toast.error("No se pudo eliminar de tu lista.");
        }
    };

    return (
        <div
            className={`bg-[#0f172a] text-white p-6 transition-all duration-500 ${
                results.length > 0 ? "min-h-screen" : "min-h-[60vh]"
            } flex flex-col items-center`}
        >
            <Toaster
                position="top-right"
                toastOptions={{
                    style: {
                        background: "#1e293b",
                        color: "#fff",
                        borderRadius: "10px",
                        fontSize: "0.9rem",
                    },
                }}
            />

            <div className="flex gap-3 mb-4">
                {["anime", "game"].map((t) => (
                    <button
                        key={t}
                        onClick={() => setType(t)}
                        className={`px-5 py-2 rounded-full text-sm font-medium transition-all ${
                            type === t
                                ? "bg-indigo-600 text-white"
                                : "bg-slate-800 text-gray-300 hover:bg-slate-700"
                        }`}
                    >
                        {t === "anime" ? "Anime" : "Videojuegos"}
                    </button>
                ))}
            </div>

            <div className="flex w-full max-w-2xl mb-6 gap-2">
                <input
                    type="text"
                    value={query}
                    onChange={(e) => setQuery(e.target.value)}
                    onKeyDown={handleKeyDown}
                    placeholder={`Buscar ${type === "anime" ? "animes" : "videojuegos"}...`}
                    
                    className="flex-1 px-4 py-3 rounded-lg bg-white border border-slate-600 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 outline-none"
                />
                <button
                    onClick={handleSearch}
                    className="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold text-sm"
                >
                    <SearchIcon className="w-6 h-6" strokeWidth={2} />
                    Buscar
                </button>

            </div>

            {query && (
                <p className="text-gray-400 text-sm mb-6">
                    Mostrando resultados para: <span className="text-indigo-400">“{query}”</span>
                </p>
            )}

            {loading ? (
                <p className="text-gray-400 text-center py-10 text-lg">Buscando...</p>
            ) : results.length > 0 ? (
                <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-5 w-full max-w-6xl">
                    {results.map((item) => (
                        <div
                            key={item.api_id || item.id}
                            className="bg-slate-800 border border-slate-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg hover:scale-[1.02] transition-all duration-200"
                        >
                            <img
                                src={item.cover_image_url}
                                alt={item.title}
                                
                                className="w-full h-44 object-cover" 
                            />
                            <div className="p-3">
                                <h3
                                    className="text-sm font-semibold text-white mb-1 truncate"
                                    title={item.title}
                                >
                                    {item.title}
                                </h3>
                                <p className="text-xs text-gray-400 mb-2 capitalize">{item.type}</p>

                                {item.in_collection ? (
                                    <button
                                        onClick={() => removeFromCollection(item)}
                                        className="w-full bg-red-600 hover:bg-red-700 text-white py-1.5 rounded-md font-semibold text-xs flex items-center justify-center gap-1 transition"
                                    >
                                        <RemoveIcon className="w-4 h-4" /> Eliminar de Mi Colección

                                    </button>
                                ) : (
                                    <button
                                        onClick={() => addToCollection(item)}
                                        className="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-1.5 rounded-md font-semibold text-xs flex items-center justify-center gap-1 transition"
                                    >
                                        <AddIcon className="w-4 h-4" /> Añadir a Mi Colección

                                    </button>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            ) : query.length > 1 ? (
                <p className="text-gray-400 text-center py-10">
                    No se encontraron resultados para “{query}”.
                </p>
            ) : (
                <p className="text-gray-500 text-center py-10">
                    Empieza a escribir para buscar.
                </p>
            )}
        </div>
    );
}