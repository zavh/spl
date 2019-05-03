import {
  SET_ACTIVE_LOANS, ADD_ACTIVE_LOAN,
 } from "../constants/action-types";

export function setActiveLoans(payload) {
  return { type: SET_ACTIVE_LOANS, payload };
};

export function addActiveLoan(payload) {
  return { type: ADD_ACTIVE_LOAN, payload };
};