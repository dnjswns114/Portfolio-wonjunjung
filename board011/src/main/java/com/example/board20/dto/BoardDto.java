package com.example.board20.dto;

import com.example.board20.domain.entity.Board;
import lombok.*;

import java.time.LocalDateTime;

@Getter
@Setter
@ToString
@NoArgsConstructor
public class BoardDto {
    private Long id;
    private String author;
    private String title;
    private String content;
    private LocalDateTime createdDate;
    private LocalDateTime modifiedDate;

    private Long boardNumber;

    private String originalFileName;
    private String savedFilePath;

    public Board toEntity() {
        Board build = Board.builder()
                .id(id)
                .author(author)
                .title(title)
                .content(content)
                .boardNumber(boardNumber)
                .originalFileName(originalFileName)
                .savedFilePath(savedFilePath)

                .build();
        return build;
    }

    @Builder
    public BoardDto(Long id, String author, String title, String content, LocalDateTime createdDate, LocalDateTime modifiedDate, Long boardNumber, String originalFileName, String savedFilePath) {
        this.id = id;
        this.author = author;
        this.title = title;
        this.content = content;
        this.createdDate = createdDate;
        this.modifiedDate = modifiedDate;
        this.boardNumber = boardNumber;
        this.originalFileName = originalFileName;
        this.savedFilePath = savedFilePath;

    }
}
