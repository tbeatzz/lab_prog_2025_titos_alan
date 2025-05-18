import {userService} from "./service.js";

export const userController = {
    list: () => {
        console.table(userService.list());
    }
}


