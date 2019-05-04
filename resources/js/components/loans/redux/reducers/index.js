import { 
  SET_ACTIVE_LOANS, ADD_ACTIVE_LOAN, MOD_ACTIVE_LOAN, SET_SCHEDULE
} from "../constants/action-types";
  
const initialState = {
  activeloans:[],
  schedule:{},
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
  if (action.type === MOD_ACTIVE_LOAN){
    // console.log(action.payload)
    let index = action.payload.index;
    let activeloans = [...state.activeloans];
    activeloans[index] = action.payload.loan;
    return Object.assign({}, state, {
      activeloans: activeloans
    });
  }
  if (action.type === SET_SCHEDULE){
    return Object.assign({}, state, {
      schedule: action.payload
    });
  }
  return state;
}
export default rootReducer;