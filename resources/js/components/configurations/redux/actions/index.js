import {
  ADD_SLAB, EDIT_SLAB, DELETE_SLAB, ADD_FS_CATEGORY, EDIT_FS_AGE, EDIT_FS_SLAB, SET_SLAB, SET_CATEGORIES, SET_FSDATA, SET_DB_STATUS
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
  return {type: SET_DB_STATUS, payload};
}