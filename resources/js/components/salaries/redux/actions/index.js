import { ADD_ARTICLE, REMOVE_ARTICLE, EDIT_ARTICLE, ADD_USERS, SET_MAIN_PANEL } from "../constants/action-types";


export function addArticle(payload) {
  return { type: ADD_ARTICLE, payload };
};

export function removeArticle(payload) {
  return { type: REMOVE_ARTICLE, payload };
};

export function editArticle(payload) {
  return { type: EDIT_ARTICLE, payload };
};

export function addUsers(payload) {
  return { type: ADD_USERS, payload };
};

export function setMainPanel(payload) {
  return { type: SET_MAIN_PANEL, payload };
};