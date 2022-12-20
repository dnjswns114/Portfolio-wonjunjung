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

    // update �Լ��� ���ھ �ǽð����� �ݿ� �ޱ� ���� �ؽ�Ʈ�� �־� �־��� OnCollisionEnter�� ���ؼ� �浹�� �߻��� ���� ī��Ʈ��
    // �����ִ� pincount�� �浹�� �߻��Ҷ����� �������� update �Լ� ���� �ǽð����� �ݿ��ϵ��� �Ͽ����ϴ�.
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
