using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;
using TMPro;

public class Manager : MonoBehaviour
{
    // Start is called before the first frame update
    public int totalItemCount;
    public int stage = 0;
    public int itemCount = 0;
    public TextMeshProUGUI OriginalText;
    public TextMeshProUGUI NowText;

    private void Awake()
     {
     OriginalText.text = "/ " + totalItemCount;
     }

     public void GetItem(int count) //사용자정의 함수
     {
     NowText.text = count.ToString();
     }

    void Start()
    {
        
    }

    // Update is called once per frame
    void Update()
    {
        
    }

    private void OnTriggerEnter2D(Collider2D collision)
 {
         if (collision.gameObject.tag == "Player")
            SceneManager.LoadScene("Game_" + stage);
  }
}
