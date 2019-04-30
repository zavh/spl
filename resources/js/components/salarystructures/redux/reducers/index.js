import { 
  SET_STRUCTURES, SET_CURRENT, SET_CONFIG, ADD_STRUCTURE, MOD_STRUCTURE, SET_CONFIG_LOADED
} from "../constants/action-types";
  
const initialState = {
  salaryStructures: [],
  current:0,
  config:{},
  configloaded:false,
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

  if (action.type === ADD_STRUCTURE){
    let ss = [...state.salaryStructures];
    ss.concat(action.payload);
    return Object.assign({}, state, {
      salaryStructures:ss
    });
  }
  
  if (action.type === MOD_STRUCTURE){
    // return Object.assign({}, state, {
    //   config:action.payload
    // });
  }

  if (action.type === SET_CONFIG_LOADED){
    return Object.assign({}, state, {
      configloaded:action.payload
    });
  }
  return state;
}
export default rootReducer;