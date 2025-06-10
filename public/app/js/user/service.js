const users = [
    { id: 1, apellido: "Titos", nombres: "Alan Daniel", cuenta: "titos.alan", correo: "titosalan2003@gmail.com", perfil: "administrador", clave: "password123", estado: "Activa", fechaCreacion: "2025-03-15" },
    { id: 2, apellido: "Pérez", nombres: "Juan", cuenta: "juanperez", correo: "juanperez@example.com", perfil: "operador", clave: "password456", estado: "Activa", fechaCreacion: "2025-03-15" },
];
export const userService = {
    load: (id) => {
        return users.find(user => user.id === id) || null;
    },
    
    save: (user) => {
        user.id = users.length + 1; 
        users.push(user);
        return user;
    },
    
    update: (user) => {
 
        const index = users.findIndex(u => u.id === user.id);
        if (index !== -1) { 
            users[index] = user;
            return users[index];
        }
    },
        
    delete: (id) => {
        const index = users.findIndex(user => user.id === id);
        if (index !== -1) {
            const deletedUser = users.splice(index, 1)[0];  
            return deletedUser; 
        }
         
    },
    
    list: (filters = {}) => {
        return users;
    }
};


