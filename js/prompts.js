/*
 * Copyright 2009 Mark J. Headd
 * 
 * This file is part of IMBills
 * 
 * IMBills is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IMBills is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with IMBills.  If not, see <http://www.gnu.org/licenses/>.NoLocationsException
 * 
 * All prompts translated with Google Translate service: http://translate.google.com/
 * The accuracy of trnslated prompts is not guaranteed.
 * 
 */


//A simple array to hold TTS engines
var engines = new Array();
engines['english'] = 'English-Male4';
engines['spanish'] = 'Spanish-Female4';

//Variables to hold prompt text
var grettingPrompt = "Welcome to the open legislative bill lookup service.";
var languageSelectionPrompt = "Para continuar en espanol, oprima 1.";

var getBillTypePrompt = new Array();
getBillTypePrompt['english'] = "'What type of bill are you interested in. For a Senate bill, press 1. For an Assembly bill, press 2.'";
getBillTypePrompt['spanish'] = "'Que tipo de documento que esta buscando. En un documento del Senado, oprima 1. Para que un documento de la Asamblea, oprima 2.'";

var getBillNumberPrompt = new Array();
getBillNumberPrompt['english'] = "'Enter the numeric portion of the bill you are looking for, followed by the pound sign.'";
getBillNumberPrompt['spanish'] = "'Introduzca la parte numerica del documento que esta buscando, a continuacion, presione la tecla numeral.'";

var pleaseHoldPrompt = new Array();
pleaseHoldPrompt['english'] = "'Hold on while I look up that bill for you.'";
pleaseHoldPrompt['spanish'] = "'Por favor, espere un momento mientras yo encontrar ese documento.'";

var buildNotFoundPrompt = new Array();
buildNotFoundPrompt['english'] = "'Sorry. I could not find the bill you are looking for.'";
buildNotFoundPrompt['spanish'] = "'Lo sentimos, no pude encontrar el documento que esta buscando.'";

var repeatMenuLookupSuccessPrompt = new Array();
repeatMenuLookupSuccessPrompt['english'] = "'To hear this information again, press 1. To look up another bill, press 2. To end this call, press 9.'";
repeatMenuLookupSuccessPrompt['spanish'] = "'Para escuchar esta informacion de nuevo, oprima 1. Para encontrar otro documento, oprima 2. Para finalizar esta llamada, oprima 9.'";

var repeatMenuLookupFailurePrompt = new Array();
repeatMenuLookupFailurePrompt['english'] = "'To look up another bill, press 1. To end this call, press 9.'";
repeatMenuLookupFailurePrompt['spanish'] = "'Para encontrar otro documento, oprima 2. Para finalizar esta llamada, oprima 9.'";

var billNumberPrompt = new Array();
billNumberPrompt['english'] = "Bill number, ";
billNumberPrompt['spanish'] = "Numero de documento, ";

var sponsoredByPrompt = new Array();
sponsoredByPrompt['english'] = "sponsored by, ";
sponsoredByPrompt['spanish'] = "Patrocinado por, ";

var lastActionPrompt = new Array();
lastActionPrompt['english'] = "last action, ";
lastActionPrompt['spanish'] = "ultima accion, ";
