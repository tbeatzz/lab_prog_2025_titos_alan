const items = [
    { id: 1, nombre: "Campera de Invierno", codigo: "CMP-001", categoria: "Ropa Nueva", precio: 45000, stock: "20", descripcion: ""},
    { id: 2, nombre: "Bufanda Vintage", codigo: "BUF-002", categoria: "Ropa Usada", precio: 15000, stock: "1", descripcion: ""},
    { id: 3, nombre: "Gorro de lana", codigo: "GOR-003", categoria: "Accesorios", precio: 10000, stock: "30", descripcion: ""},
];


export const itemService = {
    load: (id) => {
        return items.find(item => item.id === id) || null;
    },
    
    save: (item) => {
        item.id = items.length + 1; 
        items.push(item);
        return item;
    },
    
    update: (item) => {
        const index = items.findIndex(i => i.id === item.id);
        if (index !== -1) { 
            items[index] = item;
            return items[index];
        }
    },
        
    delete: (id) => {
        const index = items.findIndex(item => item.id === id);
        if (index !== -1) {
            const deleteditem = items.splice(index, 1)[0];  
            return deleteditem; 
        }   
    },
    
    list: (filters = {}) => {
        return items;
    }
};


