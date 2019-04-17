import {SET_MAIN_PANEL, SET_EMPLOYEE, SET_PAY_YEAR, SET_TAB_HEADS, SET_SALARY_ROWS, SET_REF_TIMELINE, SET_INDEXING } from "../constants/action-types";


export function addArticle(payload) {
  return { type: ADD_ARTICLE, payload };
};

export function removeArticle(payload) {
  return { type: REMOVE_ARTICLE, payload };
};

export function editArticle(payload) {
  return { type: EDIT_ARTICLE, payload };
};

export function setEmployee(payload) {
  return { type: SET_EMPLOYEE, payload };
};

export function setMainPanel(payload) {
  return { type: SET_MAIN_PANEL, payload };
};

export function setPayYear(payload) {
  return { type: SET_PAY_YEAR, payload };
};

export function setTabHeads(payload) {
  return { type: SET_TAB_HEADS, payload };
};

export function setSalaryRows(payload) {
  return { type: SET_SALARY_ROWS, payload };
};

export function setRefTimeline(payload){
  return { type: SET_REF_TIMELINE, payload};
};

export function setIndexing(payload){
  return { type: SET_INDEXING, payload};
};