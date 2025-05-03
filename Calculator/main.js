// CALCULATOR PROGRAM

// const display = document.getElementById("display");

// function appendToDisplay(input) {
//   display.value += input;
// }

// function clearDisplay() {
//   display.value = "";
// }

// function calculate() {
//   try {
//     display.value = eval(display.value.replace(/×/g, "*").replace(/÷/g, "/"));
//   } catch (error) {
//     display.value = "Error";
//   }
// }
const display = document.getElementById("display");
console.log("here = ", display);
function appendToDisplay(input) {
  display.value += input;
}

function clearDisplay() {
  display.value = "";
}

function calculate() {
  try {
    // Replace × with * and ÷ with / for proper evaluation
    const expression = display.value.replace(/×/g, "*").replace(/÷/g, "/");
    display.value = eval(expression);
  } catch (error) {
    display.value = "Error";
  }
}
