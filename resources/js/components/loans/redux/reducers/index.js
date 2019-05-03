import { 
  SET_ACTIVE_LOANS, ADD_ACTIVE_LOAN,
} from "../constants/action-types";
  
const initialState = {
  activeloans:[],
};
function rootReducer(state = initialState, action) {
  if (action.type === SET_ACTIVE_LOANS){
    return Object.assign({}, state, {
      activeloans: action.payload
    });
  }
  if (action.type === ADD_ACTIVE_LOAN){
    console.log(action.payload);
    let loans = [...state.activeloans]
    loans.concat(action.payload)
    return Object.assign({}, state, {
      activeloans: loans
    });
  }

  return state;
}
export default rootReducer;