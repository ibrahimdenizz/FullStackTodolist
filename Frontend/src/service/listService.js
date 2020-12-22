import http from "./httpService";
import {url} from "../config.json";

const endPointUrl = url + "list";

export const getList = async (userId) => {
    const {data} = await http.get(endPointUrl + "/" + userId);
    return data;
}

export const addToDO = async (content,isCheck,userId,dayId) => {
    const {data} = await http.post(endPointUrl,{content : content,is_check : isCheck,user_id : userId,day_id :dayId});
    console.log(data.message)
}

export const deleteTodo = async (id) => {
    const {data} = await http.delete(endPointUrl + "/" + id);
    console.log(data.message);
}

export const updateTodo = async (id,content,is_check) => {
    if(content && is_check){
        const {data} = await http.put(endPointUrl + "/" + id , {content,is_check});
        console.log(data.message); 
    }else if(content) {
        const {data} = await http.put(endPointUrl + "/" + id , {content});
        console.log(data.message); 
    }else if(is_check) {
        const {data} = await http.put(endPointUrl + "/" + id , {is_check});
        console.log(data.message); 
    }
}

export default {
    getList,
    addToDO,
    deleteTodo,
    updateTodo
}