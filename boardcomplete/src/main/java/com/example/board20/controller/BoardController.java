package com.example.board20.controller;

import com.example.board20.domain.entity.Board;
import com.example.board20.dto.BoardDto;
import com.example.board20.service.BoardService;
import lombok.extern.slf4j.Slf4j;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Pageable;
import org.springframework.data.domain.Sort;
import org.springframework.data.web.PageableDefault;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;

import java.io.File;
import java.io.IOException;
import java.util.List;

@Slf4j
@Controller
public class BoardController {

    private BoardService boardService;

    public BoardController(BoardService boardService) {
        this.boardService = boardService;
    }


    @GetMapping("/")
    public String list(Model model, @PageableDefault(page = 0, size = 10, sort = "id", direction = Sort.Direction.DESC) Pageable pageable) {
        Page<BoardDto> boardDtoList = boardService.getBoardList(pageable);


        int totalBoards = (int) boardDtoList.getTotalElements();

        // 최근에 작성된 게시물 번호를 맨 위에 매기도록 함 (Seq처리)
        // 보드리스트의 현재페이지 * 보드리스트의 사이즈를
        // 1페이지의 경우 133 -(0 * 10) 부터 감소연산, 2페이지의 경우 133 - (1 * 10) 부터 감소연산.
        List<BoardDto> paginatedBoards = boardDtoList.getContent();
        long boardNumber = totalBoards - (boardDtoList.getNumber() * boardDtoList.getSize());
        for (BoardDto board : paginatedBoards) {
            board.setBoardNumber(boardNumber);
            boardNumber--;
        }

        int nowPage = boardDtoList.getPageable().getPageNumber() + 1;
        int startPage = Math.max(nowPage - 4, 1);
        int endPage = Math.min(nowPage + 5, boardDtoList.getTotalPages());


        model.addAttribute("postList", paginatedBoards);
        model.addAttribute("nowPage", nowPage);
        model.addAttribute("startPage", startPage);
        model.addAttribute("endPage", endPage);

        return "board/list.html";
    }

    @GetMapping("/post")
    public String post() {
        return "board/post.html";
    }


    @PostMapping("/post")
    public String write(@ModelAttribute BoardDto boardDto, @RequestParam("uploadFile") MultipartFile uploadFile) {
        log.info("1-uploadFile(send)={}", uploadFile.getOriginalFilename());

        // Save the uploaded file
        String originalFileName = uploadFile.getOriginalFilename();
//        String savedFilePath = "C:\\uploads\\" + originalFileName; // Change this to your desired file path
        //test2를 프로젝트 이름에 맞게 변경
        String savedFilePath = "C:\\test2\\src\\main\\resources\\static\\images\\" + originalFileName; // Change this to your desired file path
        log.info("2-savedFilePath(send)={}", savedFilePath);
        // Save the file to the specified path
        try {
            uploadFile.transferTo(new File(savedFilePath));
        } catch (IOException e) {
            // Handle the exception appropriately
        }

        // Set the file details in the BoardDto
        boardDto.setOriginalFileName(originalFileName);
        boardDto.setSavedFilePath(savedFilePath);

        // Save the post with the file details
        boardService.savePost(boardDto, uploadFile);

        return "redirect:/";
    }

    @GetMapping("/post/{id}")
    public String detail(@PathVariable("id") Long id, Model model) {
        BoardDto boardDto = boardService.getPost(id);
        model.addAttribute("post", boardDto);

        model.addAttribute("fileName", boardDto.getOriginalFileName());
        model.addAttribute("filePath", (boardDto.getSavedFilePath()).substring(34, boardDto.getSavedFilePath().length()));


        return "board/detail.html";
    }

    @GetMapping("/post/edit/{id}")
    public String edit(@PathVariable("id") Long id, Model model) {
        BoardDto boardDto = boardService.getPost(id);
        model.addAttribute("post", boardDto);

        model.addAttribute("fileName", boardDto.getOriginalFileName());
        model.addAttribute("filePath", (boardDto.getSavedFilePath()).substring(34, boardDto.getSavedFilePath().length()));
        return "board/edit.html";
    }



    @PutMapping("/post/edit/{id}")
    public String update(@PathVariable("id") Long id, @ModelAttribute BoardDto updatedBoardDto, @RequestParam(value = "uploadFile", required = false) MultipartFile uploadFile) {
        BoardDto existingBoardDto = boardService.getPost(id);

        // Update the existing BoardDto with the new data
        existingBoardDto.setAuthor(updatedBoardDto.getAuthor());
        existingBoardDto.setTitle(updatedBoardDto.getTitle());
        existingBoardDto.setContent(updatedBoardDto.getContent());

        // If an upload file is provided, save it and set the file details in the BoardDto
        if (uploadFile != null && !uploadFile.isEmpty()) {
            String originalFileName = uploadFile.getOriginalFilename();
            String savedFilePath = "C:\\test2\\src\\main\\resources\\static\\images\\" + originalFileName;
            existingBoardDto.setOriginalFileName(originalFileName);
            existingBoardDto.setSavedFilePath(savedFilePath);
        }

        // Update the post in the database, including the file details if provided
        boardService.savePost(existingBoardDto, uploadFile);

        return "redirect:/post/" + id; // Redirect to the detail view of the updated post
    }

    @DeleteMapping("/post/{id}")
    public String delete(@PathVariable("id") Long id) {
        boardService.deletePost(id);
        return "redirect:/";
    }


    @GetMapping("/board/search")
    public String search(@RequestParam("keyword") String keyword, Model model,
                         @PageableDefault(page = 0, size = 500, sort = "id",
                                 direction = Sort.Direction.DESC) Pageable pageable) {


        Page<BoardDto> searchResultPage = boardService.search(keyword, pageable);
        List<BoardDto> searchList = searchResultPage.getContent();

        int nowPage = searchResultPage.getPageable().getPageNumber() + 1;
        int startPage = Math.max(nowPage - 4, 1);
        int endPage = Math.min(nowPage + 5, searchResultPage.getTotalPages());

        model.addAttribute("postList", searchList);
        model.addAttribute("nowPage", nowPage);
        model.addAttribute("startPage", startPage);
        model.addAttribute("endPage", endPage);



        return "board/searchPage";
    }

}
