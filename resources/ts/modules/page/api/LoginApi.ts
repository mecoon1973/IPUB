export class LoginApi{
    /** api login vào hệ thống */
    static async login(username : string, password : string) : Promise<boolean | null>{
        try {
            const res = await window._apiCreate("/login", { username, password });
            return res;
        }catch(err){
            console.error(err);
            return null;
        }
    }
    /** api khôi phục mật khẩu */
    static async forgetPassword(email : string) : Promise<boolean | null>{
        try {
            const res = await window._apiCreate("/forget-password", { email });
            return res;
        }catch(err){
            console.error(err);
            return null;
        }
    }
}
