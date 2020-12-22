import React, { Fragment,useEffect} from 'react';
import { Link, NavLink } from 'react-router-dom';


function Navbar({user}) {

    useEffect(()=> {
        
        
    },[]);

    return ( <div>
                    <nav className="navbar navbar-expand-lg navbar-light bg-light">
                    <Link    className="navbar-brand" to="/">ToDoList</Link>
                    <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span className="navbar-toggler-icon"></span>
                    </button>
                <div className="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul className="navbar-nav">
                    
                    {user ? <Fragment> <li className="nav-item">
                        <NavLink className="nav-link" to="/todolist/1">Monday</NavLink>
                    </li>
                    <li className="nav-item">
                        <NavLink className="nav-link" to="/todolist/2">Tuesday</NavLink>
                    </li>
                    <li className="nav-item">
                        <NavLink className="nav-link" to="/todolist/3">Wednesday</NavLink>
                    </li>
                    <li className="nav-item">
                        <NavLink className="nav-link" to="/todolist/4">Thursday</NavLink>
                    </li>
                    <li className="nav-item">
                        <NavLink className="nav-link" to="/todolist/5">Friday</NavLink>
                    </li>
                    <li className="nav-item">
                        <NavLink className="nav-link" to="/todolist/6">Saturday</NavLink>
                    </li>
                    <li className="nav-item">
                        <NavLink className="nav-link" to="/todolist/7">Sunday</NavLink>
                    </li> </Fragment> : null }
                    {!user ? <Fragment>
                    <li className="nav-item ">
                        <NavLink className="nav-link" to="/login">Login </NavLink>
                    </li>
                    <li className="nav-item">
                        <NavLink className="nav-link" to="/register">Register</NavLink>
                    </li>
                    </Fragment>:
                    <Fragment>
                        <li className="nav-item ">
                        <NavLink className="nav-link" to="/">{user.name}</NavLink>
                        </li>
                        <li className="nav-item">
                            <NavLink className="nav-link" to="/logout">Logout</NavLink>
                        </li>
                    </Fragment>
}
                    </ul>
                </div>
                </nav>
            </div> );
}


 
export default Navbar;