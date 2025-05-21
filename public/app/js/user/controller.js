import {userService} from './service.js';

export const userController = {
    list: () => {
        //Solicita al servicio las cuentas existentes y las muestra en la vista
        console.table(userService.list());
    },
    save: () => {
        //Crea una cuenta con los datos de la vista y lo envía al servicio para persistir
    },
    uptdate: () => {
        //Crea una cuenta con los datos de la vista y lo envía al servicio para persistir
    },
    delete: id => {
        //Solicita al servicio eliminar una cuenta existente y actualizar la vista
    },
    exportToPDF:() => {
        //Genera un archivo PDF
    },
    resetForm: () => {
        //Resetea un formulario de la vista y restaura los valores por defecto
    },
    enableForm:() => {
        //Habilita o deshabilita todos los controles del formulario de la vista
    },
    load: id => {
        //Solicita al servicio una cuenta existente y lo muestra en la vista
    }
}
