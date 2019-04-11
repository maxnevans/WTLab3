;(function()
{
    document.addEventListener("DOMContentLoaded", () =>
    {
        const allInputs = {};

        allInputs.birthdayDate = document.getElementById("birthday-date");
        allInputs.daysWillBe = document.getElementById("days-will-be");

        Object.keys(allInputs).forEach((inputName) => 
        {
            allInputs[inputName].addEventListener("change", sendToServer.bind(null, allInputs));
            allInputs[inputName].addEventListener("change", function()
            {
                this.classList.remove("input-error");
                document.body.querySelector(".answer").classList.remove("error");
            });
        });
        window.addEventListener("keydown", (event) => {
            if (event.key == "Enter") sendToServer.call(null, allInputs);
        });

        sendToServer.call(null, allInputs);
    });

    function sendToServer(allInputs)
    {

        const request = new XMLHttpRequest();
        request.addEventListener("readystatechange", (event) => 
        {
            switch(event.target.readyState)
            {
                case XMLHttpRequest.OPENED:
                    console.log("opened");
                    request.send(new FormData(document.forms[0]));
                    break;
                case XMLHttpRequest.DONE:
                    console.log("done");
                    const answerField = document.getElementsByClassName("answer")[0];
                    answerField.hidden = false;
                    try
                    {
                        let parsed = JSON.parse(request.response);
                        if (!parsed["error"])
                        {
                            if (!parsed["age"]) throw new SyntaxError("age field is requeried");
                            if (!parsed['year_east_name']) throw new SyntaxError("year_east_name field is requiered");
                        }

                        if (parsed.error)
                        {
                            answerField.innerHTML = "";
                            Object.keys(parsed.error.fields).forEach((fieldName) => 
                            {
                                fieldNameCamel = fieldName.replace(/_([a-z])/g, (g) => g[1].toUpperCase());
                                if (!allInputs[fieldNameCamel]) throw new Error(`there is no field ${fieldNameCamel} in allInputs`);
                                allInputs[fieldNameCamel].classList.add("input-error");
                                answerField.innerHTML += `${document.querySelector("label[for=" + fieldName.replace(/_/g, "-") + "]").textContent}`;
                                answerField.innerHTML += `${parsed.error.fields[fieldName]}`;
                                answerField.innerHTML += "<br>";
                            });
                        }
                        else
                        {
                            answerField.innerHTML = parsed.age;
                            answerField.innerHTML += "<br>";
                            answerField.innerHTML += parsed["year_east_name"];
                        }
                        console.log("Recieved:" , parsed);
                    }
                    catch(error)
                    {
                        console.log("parse failed:", request.response, error);
                        answerField.classList.add("error");
                        answerField.textContent = "Error!";
                    }
                    break;
                case XMLHttpRequest.UNSENT:
                    console.log("failed to send");
                    break;
                default:
                    console.log("something else...");
                    break;
            }
        });
        request.open("POST", "function.php");
    }
})();