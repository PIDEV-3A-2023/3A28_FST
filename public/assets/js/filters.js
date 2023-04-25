window.onload =() => {
    const FiltersForm = document.querySelector("#filters");
    document.querySelectorAll ("#filters input").forEach(input =>{
        input.addEventListener("change",()=>{
          const Form = new FormData(FiltersForm);
          const Params = new URLSearchParams();
          Form.forEach ((value, key)=>{
            Params.append(key, value);
          });
          const Url = new URL(window.location.href);
            //ajax
            fetch(Url.pathname + "?" + Params.toString(), {
                headers : {
                  "X-Requested-With" : "XMLHttpRequest" 
                }
            }).then(response => {
                console.log (response)
            }).catch(e => alert(e));
        });
    });
}