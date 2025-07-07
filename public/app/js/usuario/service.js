const BASE_URL = "http://localhost/lab_prog_2025_titos_alan/public";

export const usuarioService = {
	// Cargar usuario por ID
	load: async (id) => {
		const response = await fetch(`${BASE_URL}/usuario/load/${id}`);
		return response.json();
	},

	// Guardar nuevo usuario
	save: async (user) => {
		const response = await fetch(`${BASE_URL}/usuario/save`, {
			method: "POST",
			headers: { "Content-Type": "application/json" },
			body: JSON.stringify(user),
		});
		return response.json();
	},

	// Actualizar usuario existente
	update: async (user) => {
		try {
			console.log("Llamando a:", `${BASE_URL}/usuario/update/${user.id}`);
			console.log("Datos enviados al backend:", user);

			const response = await fetch(`${BASE_URL}/usuario/update/${user.id}`, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify(user),
			});

			const result = await response.json();
			console.log("Respuesta del servidor:", result);

			if (!response.ok) {
				throw new Error(
					`Error ${response.status}: ${result.message || response.statusText}`
				);
			}

			return result;
		} catch (error) {
			console.error("Error en usuarioService.update:", error);
			throw error;
		}
	},

	// Eliminar usuario por ID (por GET)
	delete: async (id) => {
		const response = await fetch(`${BASE_URL}/usuario/delete/${id}`, {
			method: "POST",
			headers: { "Content-Type": "application/json" },
			body: JSON.stringify({ id }),
		});
		return response.json();
	},

	// Listar usuarios con filtros opcionales
	list: async (filters = {}) => {
		try {
			// Filtrar valores vacíos o nulos
			const cleanedFilters = {};
			for (const key in filters) {
				if (
					filters[key] !== undefined &&
					filters[key] !== null &&
					filters[key] !== ""
				) {
					cleanedFilters[key] = filters[key];
				}
			}

			const response = await fetch(`${BASE_URL}/usuario/list`, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify(cleanedFilters),
			});

			if (!response.ok) {
				throw new Error(`Error ${response.status}: ${response.statusText}`);
			}

			return response.json();
		} catch (error) {
			console.error("Error en usuarioService.list:", error);
			throw error;
		}
	},
	// Exportar lista de usuarios a PDF
	exportPdf: async (filters = {}) => {
		try {
			// Filtrar valores vacíos o nulos
			const cleanedFilters = {};
			for (const key in filters) {
				if (
					filters[key] !== undefined &&
					filters[key] !== null &&
					filters[key] !== ""
				) {
					cleanedFilters[key] = filters[key];
				}
			}

			// Hacer solicitud POST al endpoint de exportación
			const response = await fetch(`${BASE_URL}/usuario/exportPdf`, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify(cleanedFilters),
			});

			if (!response.ok) {
				throw new Error(`Error ${response.status}: ${response.statusText}`);
			}

			// Obtener el blob del PDF y desencadenar la descarga
			const blob = await response.blob();
			const url = window.URL.createObjectURL(blob);
			const a = document.createElement("a");
			a.href = url;
			a.download = `usuarios_${new Date()
				.toISOString()
				.replace(/[:.]/g, "")}.pdf`;
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
			window.URL.revokeObjectURL(url);
		} catch (error) {
			console.error("Error en usuarioService.exportPdf:", error);
			throw error;
		}
	},

	// Exportar datos de un usuario a PDF
	exportSinglePdf: async (id) => {
		try {
			window.location.href = `${BASE_URL}/usuario/exportSinglePdf/${id}`;
		} catch (error) {
			console.error("Error en usuarioService.exportSinglePdf:", error);
			throw error;
		}
	},

	
};
