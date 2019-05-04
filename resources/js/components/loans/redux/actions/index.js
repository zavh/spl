import {
  SET_ACTIVE_LOANS, ADD_ACTIVE_LOAN, MOD_ACTIVE_LOAN, SET_SCHEDULE
 } from "../constants/action-types";

export function setActiveLoans(payload) {
  return { type: SET_ACTIVE_LOANS, payload };
};

export function addActiveLoan(payload) {
  return { type: ADD_ACTIVE_LOAN, payload };
};

export function modActiveLoan(payload) {
  return { type: MOD_ACTIVE_LOAN, payload };
};

export function setSchedule(payload) {
  return { type: SET_SCHEDULE, payload };
};