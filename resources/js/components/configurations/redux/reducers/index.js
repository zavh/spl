import { 
  ADD_SLAB, EDIT_SLAB, DELETE_SLAB, ADD_FS_CATEGORY, EDIT_FS_DATA,
} from "../constants/action-types";
  
const initialState = {
  slabs:[],
  firstSlabCategories:{},
  fsdata:{},
};
function rootReducer(state = initialState, action) {
  if (action.type === ADD_SLAB){
    let slabs = [...state.slabs].concat(action.payload);
    return Object.assign({}, state, {
      slabs: slabs
    });
  }
  
  if (action.type === EDIT_SLAB){
    let slabs = [...state.slabs];
    slabs[action.payload.index].slabval = action.payload.slabval;
    slabs[action.payload.index].percval = action.payload.percval;
    return Object.assign({}, state, {
      slabs: slabs
    });
  }
  
  if (action.type === DELETE_SLAB){
    let index = action.payload;
    let s = [...state.slabs], modslabs = [];
    for(var i=0;i<s.length;i++){
      if(i == index) continue;
      modslabs[modslabs.length] = s[i];
    }
    return Object.assign({}, state, {
      slabs: modslabs
    });
  }
  
  if (action.type === ADD_FS_CATEGORY){
    let catkey = '';
    for(var key in action.payload)
      catkey = key;
    let categories = Object.assign({},state.firstSlabCategories, action.payload);
    let slab = {}, age = [], newfsdata={}, fsdata={};
    slab['any'] = '100000'; //some default value
    age[0] = 'any'; //some default value
    newfsdata[catkey] = {
        age:age,
        slab:slab,
    }
    fsdata = Object.assign({}, state.fsdata, newfsdata);
    return Object.assign({}, state, {
      firstSlabCategories:categories,
      fsdata:fsdata
    });
  }

  if (action.type === EDIT_FS_DATA){
    let category = action.payload.key;
    let edited = Object.assign({},state.fsdata,action.payload.update);
  
    return Object.assign({}, state, {fsdata : edited})
  }

  return state;
}
export default rootReducer;