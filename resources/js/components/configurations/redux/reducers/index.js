import { 
  ADD_SLAB, EDIT_SLAB, DELETE_SLAB, ADD_FS_CATEGORY, EDIT_FS_AGE, EDIT_FS_SLAB,
} from "../constants/action-types";
  
const initialState = {
  slabs:[],
  firstSlabCategories:{},
  fsdata:{},
  fserrors:[],
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
    slab['any'] = 100000; //some default value
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

  if (action.type === EDIT_FS_AGE){
    let index = action.payload.index;
    let value = action.payload.value;
    let edited = Object.assign({},state.fsdata,action.payload.update);
    let errors = [...state.fserrors];
    errors[index] = '';
    if(index == 0){
      if(!isNaN(value)){ //it's a valid number
        if(value == '')
          errors[0] = "'Age' should be either 'any' or a numeric value";
        else{
          let category = action.payload.category;
          let newel = (parseInt(value) - 1).toString();
          edited[category].age[parseInt(index)+1] = newel;
          edited[category].slab[newel] = 100000;
          let age = edited[category].age;
          let newslab = {};
          for(var i=0;i<age.length;i++){
            if(age[i] in edited[category].slab)
              newslab[age[i]] = edited[category].slab[age[i]];
          }
          edited[category].slab = newslab;          
        }
      }
      else if(value != 'any'){
        errors[0] = "'Age' should be either 'any' or a numeric value";
      }
    }
    
    return Object.assign({}, state, {fsdata : edited, fserrors:errors})
  }

  if (action.type === EDIT_FS_SLAB){
    let edited = Object.assign({},state.fsdata,action.payload.update);
    return Object.assign({}, state, {fsdata : edited})
    // console.log(edited);
  }

  return state;
}
export default rootReducer;