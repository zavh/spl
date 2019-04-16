import { SET_MAIN_PANEL, SET_EMPLOYEE, SET_PAY_YEAR, SET_TAB_HEADS, SET_SALARY_ROWS } from "../constants/action-types";
const initialState = {
  session: {}
};
function rootReducer(state = initialState, action) {
  if (action.type === SET_MAIN_PANEL){
    return Object.assign({}, state, {
      mainPanel: action.payload
    });
  }
  if (action.type === SET_EMPLOYEE){
    return Object.assign({}, state, {
      targetEmployee: action.payload
    });
  }
  if (action.type === SET_PAY_YEAR){
    return Object.assign({}, state, {
      timeline: action.payload
    });
  }
  if (action.type === SET_TAB_HEADS){
    return Object.assign({}, state, {
      tabheads: action.payload
    });
  }
  if (action.type === SET_SALARY_ROWS){
    let rows = Object.keys(action.payload).map(function(key){
      return action.payload[key]
    });
    return Object.assign({}, state, {
      salaryrows: rows
    });

  }
  // if (action.type === GET_PIS){
  //   return Object.assign({}, state, {
  //     pis: action.payload
  //   });
  // }
  // if (action.type === EDIT_PI) {
  //   let pis = [...state.pis];
  //   pis[action.payload.index]['cus_id'] = action.payload.cus_id;
  //   pis[action.payload.index]['customer_id'] = action.payload.customer_id;
  //   pis[action.payload.index]['owner_name'] = action.payload.owner_name;
  //   pis[action.payload.index]['status'] = action.payload.status;
  //   return Object.assign({}, state, {
  //     pis: pis
  //   });
  // }

  return state;
}
export default rootReducer;