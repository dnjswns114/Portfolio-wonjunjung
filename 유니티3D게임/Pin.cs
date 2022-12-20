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

    // update 함수에 스코어를 실시간으로 반영 받기 위해 텍스트를 넣어 주었고 OnCollisionEnter를 통해서 충돌이 발생한 핀의 카운트를
    // 세어주는 pincount를 충돌이 발생할때마다 증가시켜 update 함수 내에 실시간으로 반영하도록 하였습니다.
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
