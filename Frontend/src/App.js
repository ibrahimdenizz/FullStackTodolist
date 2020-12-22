import React , {useState,useEffect}  from 'react';
import { Redirect, Route, Switch } from 'react-router-dom';
import Navbar from './component/Navbar';
import Login from './component/Login';
import Register from './component/Register';
import ToDoList from './component/ToDoList';
import Logout from './component/Logout';
import './App.css';
import { getCurrentUser } from './service/userService';
import { getList } from './service/listService';

function App() {
  const [user,setUser]= useState(null)
  const [list,setList]= useState(null)
  useEffect( ()=> {
    //Look local stroge and get jwt key
    setUser(getCurrentUser());
    
  },[])

  useEffect( () => {
    
    async function getData() {
      try{
        const temp = await getList(user.id);
        setList([...temp]);
        } catch(ex) {
          console.log(ex);
        } 
      }
    

    if(user){
      getData();
    }
  },[user])

  return (
    <div >
     <Navbar user={user} />
     <Switch >
        <Route path="/login" component={Login} />
        <Route path="/logout" component={Logout} />
        <Route path="/register" component={Register} />
        <Route path="/todolist/:id" component={()=><ToDoList user={user ? user: 0} list={list} setList={setList} />} />
        <Redirect to="/todolist/1"/>
     </Switch>
    </div>
  );
}

export default App;
