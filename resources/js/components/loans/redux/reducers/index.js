import { 
  SET_ACTIVE_LOANS, 
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

  return state;
}
export default rootReducer;