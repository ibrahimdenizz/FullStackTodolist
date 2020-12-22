import React, { useState, useEffect, useCallback, Fragment } from "react";
import { useParams } from "react-router-dom";
import {
  addToDO,
  getList,
  deleteTodo,
  updateTodo,
} from "../service/listService";

const ToDoList = ({ list, user, setList }) => {
  const days = [
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
    "Sunday",
  ];
  const { id: day } = useParams();
  const [update,setUpdate] = useState(0);
  const [newTodo, setNewTodo] = useState({
    content: "",
    isCheck: false,
    dayId: day,
    userId: user ? user.id : 0,
  });
  const [newContent,setNewContent] = useState("");

  const getDay = useCallback(() => {
    setNewTodo({
      userId: user ? user.id : 0,
      content: "",
      isCheck: false,
      dayId: day,
    });
  }, [day, user]);

  useEffect(() => {
    getDay();
  }, [day, getDay]);

  const handleAddTodo = async () => {
    try {
      await addToDO(newTodo.content, newTodo.isCheck, newTodo.userId, newTodo.dayId);
      const temp = await getList(user.id);
      setList([...temp]);
    } catch (ex) {
      console.log(ex);
    }
  };

  const handleDeleteTodo = async (id) => {
    try {
      await deleteTodo(id);
      const temp = await getList(user.id);
      setList([...temp]);
    } catch (ex) {
      console.log(ex);
    }
  };

  const handleChecked = async (item) => {
    try {
      const is_check = item.is_check === "1" ? "0" : "1";
      await updateTodo(item.id, null, is_check);
      const temp = await getList(user.id);
      setList([...temp]);
    } catch (ex) {
      console.log(ex);
    }
  };
  
  const handleUpdate = async (item) => {
    try {
      await updateTodo(item.id,newContent,null);
      const temp = await getList(user.id);
      setList([...temp]);
    } catch (error) {
      console.log(error)
    }
  }

  return (
    <div className=" container mt-5">
      <h2 className="row">To do List {days[day - 1]}</h2>
      <div className="row">
        <input
          type="text"
          className="form-control "
          style={{ width: 400 }}
          value={newTodo.content}
          onChange={(e) => setNewTodo({ ...newTodo, content: e.target.value })}
          aria-describedby="helpId"
          placeholder="Add Todo"
        />
        <br></br>
        <button onClick={handleAddTodo} className="btn btn-primary mx-3">
          Add
        </button>
      </div>
      <div className="my-5"></div>
      {list
        ? list
            .filter((item) => item.day_id === day)
            .map((item) => {
              const checked = item.is_check === "1" ? true : false;
              return (
                <div key={item.id} className="row my-3">
                  <input
                    className="col-md-auto mb-3"
                    defaultChecked={checked}
                    onChange={() => handleChecked(item)}
                    type="checkbox"
                  ></input>
                  {(item.id === update) ? <input type="text" className="form-control col col-lg-7" value={newContent} onChange={(e) => setNewContent(e.target.value )}></input> : <span className="col col-lg-7"> {item.content}</span>}
                  {(item.id === update)? 
                  <Fragment>
                    <button
                      onClick={() => handleUpdate(item)}
                      className="btn btn-success mx-2 col-md-auto px-4"
                      >
                      OK
                    </button>
                    <button
                      onClick={() =>{setUpdate(0); setNewContent("")}}
                      className="btn btn-danger mx-2 col-md-auto"
                      >
                      Cancel
                    </button> 
                  </Fragment> 
                  :
                  <Fragment>
                    <button
                      onClick={() => {setUpdate(item.id); setNewContent(item.content);}}
                      className="btn btn-primary mx-2 col-md-auto"
                      >
                      Update
                    </button>
                    <button
                      onClick={() => handleDeleteTodo(item.id)}
                      className="btn btn-danger mx-2 col-md-auto"
                      >
                      Delete
                    </button>
                  </Fragment> 
                  }
                </div>
              );
            })
        : null}
    </div>
  );
};

export default ToDoList;
