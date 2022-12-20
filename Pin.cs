using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using TMPro;

public class Pin : MonoBehaviour
{
    Rigidbody rigid;
    // Start is called before the first frame update
    public float pincount;
    public TextMeshProUGUI score;
    void Start()
    {
        rigid = GetComponent<Rigidbody>();
        pincount = -1;
        
    }

    // Update is called once per frame
    void Update()
    {
       
        score.text = "Score: " + pincount.ToString() + "/(spare)";

        if(pincount >= 10){
            score.text = "STRIKE!!";
        }
 
    }

    void OnCollisionEnter(Collision collision){

        
        pincount++;
       
 

       
    }

 

}
