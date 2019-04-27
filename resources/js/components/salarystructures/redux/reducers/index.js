import { 
  SET_STRUCTURES, SET_CURRENT, SET_CONFIG, MOD_STRUCTURE,
} from "../constants/action-types";
  
const initialState = {
  salaryStructures: [],
  current:0,
  config:{},

};
function rootReducer(state = initialState, action) {
  if (action.type === SET_STRUCTURES){
    return Object.assign({}, state, {
      salaryStructures: action.payload
    });
  }

  if (action.type === SET_CURRENT){
    return Object.assign({}, state, {
      current: action.payload
    });
  }

  if (action.type === SET_CONFIG){
    return Object.assign({}, state, {
      config:action.payload
    });
  }

  if (action.type === MOD_STRUCTURE){
    console.log(action.payload);
    return Object.assign({}, state, {
      config:action.payload
    });
  }
  return state;
}
export default rootReducer;