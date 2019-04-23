import {
  ADD_SLAB, EDIT_SLAB, DELETE_SLAB, ADD_FS_CATEGORY, EDIT_FS_AGE, EDIT_FS_SLAB,
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
