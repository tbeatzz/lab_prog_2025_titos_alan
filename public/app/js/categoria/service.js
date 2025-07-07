const BASE_URL = "http://localhost/lab_prog_2025_titos_alan/public";

export const categoriaService = {


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

			const response = await fetch(`${BASE_URL}/categoria/list`, {
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
			console.error("Error en categoriaService.list:", error);
			throw error;
		}
	},

	// Guardar nueva categorua
	save: async (categoria) => {
		const response = await fetch(`${BASE_URL}/categoria/save`, {
			method: "POST",
			headers: { "Content-Type": "application/json" },
			body: JSON.stringify(categoria),
		});
		return response.json();
	},

	// Eliminar categoria por ID (por GET)
	delete: async (id) => {
		const response = await fetch(`${BASE_URL}/categoria/delete/${id}`, {
			method: "POST",
			headers: { "Content-Type": "application/json" },
			body: JSON.stringify({ id }),
		});
		return response.json();
	},

	// Cargar categoria por ID
	load: async (id) => {
		const response = await fetch(`${BASE_URL}/categoria/load/${id}`);
		return response.json();
	},
	// Actualizar categoria existente
	update: async (user) => {
		try {
			console.log("Llamando a:", `${BASE_URL}/categoria/update/${user.id}`);
			console.log("Datos enviados al backend:", user);

			const response = await fetch(`${BASE_URL}/categoria/update/${user.id}`, {
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
			const response = await fetch(`${BASE_URL}/categoria/exportPdf`, {
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
			a.download = `categoria_${new Date()
				.toISOString()
				.replace(/[:.]/g, "")}.pdf`;
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
			window.URL.revokeObjectURL(url);
		} catch (error) {
			console.error("Error en categoriaService.exportPdf:", error);
			throw error;
		}
	},

	// Exportar datos de una categoria a PDF
	exportSinglePdf: async (id) => {
		try {
			window.location.href = `${BASE_URL}/categoria/exportSinglePdf/${id}`;
		} catch (error) {
			console.error("Error en categoriaService.exportSinglePdf:", error);
			throw error;
		}
	},


};
