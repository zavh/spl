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
    let loans = [...state.activeloans];
    let newloan = [];
    newloan[0] = action.payload;
    loans = loans.concat(newloan);
    console.log(loans);
    return Object.assign({}, state, {
      activeloans: loans
    });
  }

  return state;
}
export default rootReducer;