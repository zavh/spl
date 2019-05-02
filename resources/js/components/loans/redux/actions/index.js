import {
  SET_ACTIVE_LOANS,
 } from "../constants/action-types";


export function setActiveLoans(payload) {
  return { type: SET_ACTIVE_LOANS, payload };
};
