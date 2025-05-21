const users = [
    {id: 1, apellido:"Titos", nombres:"Alan Daniel", cuenta: "titos.alan", correo: "titosalan2003@gmail.com"},
    {id: 2, apellido:"Perez", nombres:"Juan", cuenta: "juan.perez", correo: "juanperez@ejemplo.com"},
];

export const userService ={
    load: id =>{
        

        return users.find(user => user.id == id);
        //recorrer users hasta el id
        //return users[pos] devuelve un objeto usuario
    },
    save: user =>{
        // peticion asincrona
        user.id=users.length();
        users.push(user);
    },
    update: user =>{
        
    },
    delete: id=>{

    },
    list: filters =>{
        return users;
    },
}   


let newUser = {};
newUser.id=5;
newUser.apellido="apellido";
newUser.nombres="jose";
newUser.cuenta = "jose.rasjido";
newUser.correo = "jose.rasjido@gmail.com"

