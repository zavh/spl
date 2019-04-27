import {
  SET_STRUCTURES, SET_CURRENT, SET_CONFIG, MOD_STRUCTURE

 } from "../constants/action-types";


export function setStructures(payload) {
  return { type: SET_STRUCTURES, payload };
};

export function setConfig(payload) {
  return { type: SET_CONFIG, payload };
};

export function setCurrent(payload) {
  return { type: SET_CURRENT, payload };
};

export function modStructure(payload) {
  return { type: MOD_STRUCTURE, payload };
};



