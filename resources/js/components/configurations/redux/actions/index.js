import {
  ADD_SLAB,
  EDIT_SLAB,
  DELETE_SLAB,
  ADD_FS_CATEGORY,
  EDIT_FS_AGE,
  EDIT_FS_SLAB,
  SET_SLAB,
  SET_CATEGORIES,
  SET_FSDATA,
  SET_SLAB_DB_STATUS,
  SET_SLAB_INITIATION,
  ADD_SALARY_HEAD,
  SET_SALARY_HEAD,
  MOD_SALARY_CONFIG,
  SET_HEAD_INITIATION,
  SET_SLAB_SAVINGS,
  SET_HEAD_SAVINGS,
 } from "../constants/action-types";

export function addSlab(payload) {
  return { type: ADD_SLAB, payload };
};

export function editSlab(payload) {
  return { type: EDIT_SLAB, payload };
};

export function deleteSlab(payload) {
  return { type: DELETE_SLAB, payload };
};

export function addFSCategory(payload) {
  return { type: ADD_FS_CATEGORY, payload };
};

export function editFSAge(payload) {
  return { type: EDIT_FS_AGE, payload };
};

export function editFSSlab(payload) {
  return { type: EDIT_FS_SLAB, payload };
};

export function setSlab(payload){
  return {type: SET_SLAB, payload};
}

export function setCategories(payload){
  return {type: SET_CATEGORIES, payload};
}

export function setFSData(payload){
  return {type: SET_FSDATA, payload};
}

export function setSlabDBStatus(payload){
  return {type: SET_SLAB_DB_STATUS, payload};
}

export function setSlabInitiation(payload){
  return {type: SET_SLAB_INITIATION, payload};
}

export function addSalaryHead(payload){
  return {type: ADD_SALARY_HEAD, payload};
}

export function setSalaryHead(payload){
  return {type: SET_SALARY_HEAD, payload};
}

export function modSalaryConfig(payload){
  return {type: MOD_SALARY_CONFIG, payload};
}

export function setHeadInitiation(payload){
  return {type: SET_HEAD_INITIATION, payload};
}

export function setSlabSavingsFlag(payload){
  return {type: SET_SLAB_SAVINGS, payload};
}
export function setHeadSaveFlag(payload){
  return {type: SET_HEAD_SAVINGS, payload};
}

